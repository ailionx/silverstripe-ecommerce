<% loop $showCartItems %>
    <div>
        <div>Product Name: $Product.Name</div>
        <div style="background-image: url('$Product.getDefaultImage.Filename'); background-size:contain; width: 80px; height: 80px;"></div>
        <div>Price: 
            <% with $Spec %>
                <% loop $Currencies %>
                    <div>$Currency: $Price</div>
                <% end_loop %>
            <% end_with %>
        </div>
        <div>Quantity: $Quantity</div>
        <a href="ecommerce/checkout"><button>Checkout</button></a>
    </div>
<% end_loop %>