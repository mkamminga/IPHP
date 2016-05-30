<?php
namespace IPHP\File;

class File {
	public function move (string $from, string $to):bool {
		return rename($from, $to);
	}

	public function remove ($file):bool {
		return file_exists($file) && unlink($file);
	}

	public function exists ($path):bool {
		return file_exists($path);
	}

	public function createDir (string $dir, $rights = '0755'):bool {
		if (!is_dir($dir)) {
			if (!mkdir($dir)){
				throw new \Exception("Could\'t create dir: ". $dir);
			}
		}

		return true;
	} 

	public function removeDir ($dir, $removeContents = true):bool {
		if (is_dir($dir)){
			if ($removeContents){
				$files = array_diff(scandir($dir), array('.','..'));

				foreach ($files as $file) {
					$path = $dir. DIRECTORY_SEPARATOR . $file;

					if (is_dir($path)){
						if (!$this->removeDir($path, true)) {
							return false;
						}
					} else if (!$this->remove($path)){
						throw new \Exception("Error removing file '". $file ."' in dir '". $dir ."'");
					}
				}
			}

			return rmdir($dir);
		}

		return false;
	}

	public function getName ($filepath):string {
		return basename($filepath);
	}

	public function getExtension ($filepath):string {
		$parts = pathinfo($filepath);

		if (isset($parts['extension'])) {
			return $parts['extension'];
		}

		return '';
	}

	public function normilizeName ($name):string {
		return preg_replace('/([^a-zA-Z_\.]+)/', '_', $name);
	}
}