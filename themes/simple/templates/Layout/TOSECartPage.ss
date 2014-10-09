<div style="float:right;">
    <a href='$Link/clearCart'><button>Clear Cart</button></a>
</div>
<% loop $getCartItems %>
    <div>
        <div>Product Name: $Product.Name</div>
        <div style="background-image: url('$Product.getDefaultImage.Filename'); background-size:contain; width: 80px; height: 80px; background-repeat: no-repeat;"></div>
        <% with $Spec %>
            <% if $ExtraInfo %><div>Specification: $ExtraInfo</div><% end_if %>
            <div>Price: 
                <% loop $Currencies %>
                    <div>$Currency: $Price</div>
                <% end_loop %>
            </div>
        <% end_with %>
        <div>Quantity: $Quantity</div>
    </div>
<% end_loop %>
<a href="ecommerce/checkout"><button>Checkout</button></a>