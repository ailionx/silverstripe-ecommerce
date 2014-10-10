<div style="float:right;">
    <a href='$Link/clearCart'><button>Clear Cart</button></a>
</div>
<% with $Cart %>
    <% loop $getCartItems %>
        <div>
            <div>Product Name: $Product.Name</div>
            <div style="background-image: url('$Product.getDefaultImage.Filename'); background-size:contain; width: 80px; height: 80px; background-repeat: no-repeat;"></div>
            <% with $Spec %>
                <% if $ExtraInfo %><div>Specification: $ExtraInfo</div><% end_if %>
                <% with $getCurrency %>
                    <div>$Currency: $get_current_currency_symbol$priceFormatted</div>
                <% end_with %>
            <% end_with %>
            <form method="post" action="{$Top.Link}updateItem">
            <div>Quantity: <input type="text" value="$Quantity" name="Quantity" style="width: 30px" /></div>
            <div>SubTotal: $Top.getCurrentCurrencySymbol$subTotalPriceFormatted</div>
            <input type="hidden" value="$Product.ID" name="ProductID" />
            <input type="hidden" value="$Spec.ID" name="SpecID" />
            <button style="float: none;" type="submit">update</button>
            </form>
            <a href="$Top.Link()deleteItem?ProductID=$ProductID&SpecID=$SpecID"><button style="float: none;">delete</button></a>
        </div>
    <% end_loop %>
    <div>Total Price: $Top.getCurrentCurrencySymbol$totalPriceFormatted</div>
<% end_with %>
<a href="ecommerce/checkout"><button>Checkout</button></a>