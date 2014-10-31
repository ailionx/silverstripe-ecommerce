<% with Product %>
    <div>$Name</div>
    <div>$Description</div>
    <% loop Images %>
        <img src="$Filename" style="width:100px;">
    <% end_loop %>
    <form method="post" action="{$getCartLink}addToCart">
        <ul>
            <% loop Specs %>
                <li style="float: left; margin-right: 30px;">
                    <input type="radio" name="SpecID" value="$ID" />&nbsp;&nbsp;Specification:
                    <div>$ExtraInfo</div>
                    <div>Weight: $Weight</div>
                    <div>SKU: $SKU</div>
                    <div>Inventory: $Inventory</div>
                    <% loop Prices %>
                        <div>$Nice</div>
                    <% end_loop %>
                </li>
            <% end_loop %>
        </ul>
        <div style="clear:both">
            Quantity: <input type="text" name="Quantity" />
        </div>
        <input type="submit" value="add to cart" />
    </form>

<% end_with %>