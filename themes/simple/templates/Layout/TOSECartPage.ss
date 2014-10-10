<div style="float:right;">
    <a href='$Link/clearCart'><button>Clear Cart</button></a>
</div>
<% with $Cart %>
    <% loop $CartItems %>
        <div>
            <div>Product Name: $Product.Name</div>
            <div style="background-image: url('$Product.DefaultImage.Filename'); background-size:contain; width: 80px; height: 80px; background-repeat: no-repeat;"></div>
            <% with $Spec %>
                <% if $ExtraInfo %><div>Specification: $ExtraInfo</div><% end_if %>
                <% with $Currency %>
                    <div>$Currency: $get_current_currency_symbol$priceFormatted</div>
                <% end_with %>
            <% end_with %>
            <form method="post" action="{$Top.Link}updateItem">
            <div>Quantity: <input type="number" value="$Quantity" name="Quantity" style="width: 30px" /></div>
            <div>SubTotal: $Top.CurrentCurrencySymbol$subTotalPriceFormatted</div>
            <input type="hidden" value="$Product.ID" name="ProductID" />
            <input type="hidden" value="$Spec.ID" name="SpecID" />
            <button style="float: none;" type="submit">update</button>
            </form>
            <a href="$Top.Link()deleteItem?ProductID=$ProductID&SpecID=$SpecID"><button style="float: none;">delete</button></a>
        </div>
    <% end_loop %>
    <div>Total Price: $Top.CurrentCurrencySymbol$totalPriceFormatted</div>
<% end_with %>
<a href="ecommerce/checkout"><button>Checkout</button></a>