<form>
    <h2 class="h2-state">{{$state}}</h2>
    @if (count($shoppingcart) > 0)
        <table class="t-shoppingcart">
            <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Remove</th>
                
            </tr>
            </thead>

                <tbody class="tb-cart">
                @foreach($shoppingcart as $item)
                    <tr data-id="{{ $item->id }}" class="cart-row-item">
                        <td>
                            <a class="th" href="#"><img style="max-height: 80px; max-width: 80px;" src="{{ relative_images_path() . '/'. $item->artikelnr . '/' . $item->small_image_link }}"></a>
                        </td>

                        <td>
                            {{ $item->name }}
                        </td>

                        <td class="td-quantity">
                            <input type="text" value="{{ $item->quantity }}" class="cart-item-quantity small" style="width: 3em;" />
                        </td>

                        <td>
                            &euro; {{ number_format($item->price, 2, ',', '.') }}
                        </td>

                        <td>  &euro; {{ number_format($item->subtotal, 2, ',', '.') }}</td>

                        <td>
                            <a href="#"><i class="fi-x remove-item" data-id="{{ $item->id }}"></i></a>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Totalprice</strong></td>
                    <td colspan="2">&euro; {{ number_format($totalprice, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif
</form>