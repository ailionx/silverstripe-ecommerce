<% loop $getCartItems %>
    <div>
        <div style="background-image: url('$Product.DefaultImage.Filename'); background-size:contain; width: 80px; height: 80px; background-repeat: no-repeat;"></div>
        <div>Product Name: $Product.Name</div>
        <% with $Spec %>
            <% if $ExtraInfo %><div>Specification: $ExtraInfo</div><% end_if %>
            <% with $CurrentPrice %>
                <div>Price: $Nice</div>
            <% end_with %>
        <% end_with %>
        <div>Quantity: $Quantity</div>
        <div>SubTotal: $CurrencySymbol$subTotalPrice.Nice</div>
    </div>
<% end_loop %>
<div>Total Price: $CurrencySymbol$totalPrice.Nice</div>