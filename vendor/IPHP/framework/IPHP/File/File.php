<?php
namespace IPHP\File;

class File {
	public function move ($from, $to):bool {
		return rename($from, $to);
	}

	public function remove ($file):bool {
		return unlink($file);
	}

	public function createDir (string $dir, $name, $rights = '0755'):bool {
		$dir = $dir . DIRECTORY_SEPARATOR . $name;
		if (!is_dir($dir)) {
			return mkdir($dir, $rights, $rights);
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

	public function getExtension ($filepath) {
		return basename($filepath);
	}

	public function getUploadableFileExtension (array $file) {
		if (isset($file["name"])) {
			return $this->getExtension($file["name"]);
		} 

		return '';
	}
}