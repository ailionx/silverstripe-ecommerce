<% loop $CartItems %>
    <div>
        <div style="background-image: url('$Product.DefaultImage.Filename'); background-size:contain; width: 80px; height: 80px; background-repeat: no-repeat;"></div>
        <div>Product Name: $Product.Name</div>
        <% with $Spec %>
            <% if $ExtraInfo %><div>Specification: $ExtraInfo</div><% end_if %>
            <% with $CurrentPrice %>
                <div>Price: $Currency $get_current_currency_symbol$priceFormatted</div>
            <% end_with %>
        <% end_with %>
        <form method="post" action="{$CartLink}updateItem">
        <div>Quantity: <input type="number" value="$Quantity" name="Quantity" style="width: 40px" /></div>
        <div>SubTotal: $CurrencySymbol$subTotalPriceFormatted</div>
        <input type="hidden" value="$Product.ID" name="ProductID" />
        <input type="hidden" value="$Spec.ID" name="SpecID" />
        <button type="submit">update</button>
        </form>
        <a href="{$CartLink}deleteItem?ProductID=$ProductID&SpecID=$SpecID"><button style="float: none;">delete</button></a>
    </div>
<% end_loop %>
<div>Total Price: $CurrencySymbol$totalPriceFormatted</div>