<% with Product %>
    <div>$Name</div>
    <div>$Description</div>
    <% loop Images %>
        <img src="$Filename" style="width:60px;">
    <% end_loop %>
    <form method="post" action="{$getCartLink}/addToCart">
        <ul>
            <% loop Specs %>
                <li>
                    <input type="radio" name="spec" value="$ID" />
                    <div>Specification: $ExtraInfo</div>
                    <div>Weight: $Weight</div>
                    <div>SKU: $SKU</div>
                    <div>Inventory: $Inventory</div>
                </li>
            <% end_loop %>
        </ul>
        <div>
            Quantity: <input type="text" name="quantity" />
        </div>
        <input type="submit" value="add to cart" />
    </form>

<% end_with %>