<div style="float:right;">
    <a href='$Link/clearCart'><button>Clear Cart</button></a>
</div>
<% loop $getCartItems %>
    <div>
        <div>Product Name: $Product.Name</div>
        <div style="background-image: url('$Product.getDefaultImage.Filename'); background-size:contain; width: 80px; height: 80px; background-repeat: no-repeat;"></div>
        <% with $Spec %>
            <% if $ExtraInfo %><div>Specification: $ExtraInfo</div><% end_if %>
            <% with $getCurrentCurrency %>
                <div>$Currency: $Top.getCurrentCurrencySymbol $Price</div>
            <% end_with %>
        <% end_with %>
        <form method="post" action="{$Top.Link}refreshItem">
        <div>Quantity: <input type="text" value="$Quantity" name="Quantity" style="width: 30px" /></div>
        <div>SubTotal: $getCurrentCurrencySymbol $subTotal</div>
        <input type="hidden" value="$Product.ID" name="ProductID" />
        <input type="hidden" value="$Spec.ID" name="SpecID" />
        <button style="float: none;" type="submit">update</button></a>
        </form>
    </div>
<% end_loop %>
<a href="ecommerce/checkout"><button>Checkout</button></a>