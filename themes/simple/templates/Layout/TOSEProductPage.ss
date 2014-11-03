<% with Product %>
    <div>$Name</div>
    <div>$Description</div>
    <% loop Images %>
        <img src="$Filename" style="width:100px;">
    <% end_loop %>
    <form method="post" action="{$getCartLink}addToCart">
        <% loop Specs %>
            <ul>
                <li style="float: left; margin-right: 30px;">
                    <input type="radio" name="SpecID" value="$ID" />&nbsp;&nbsp;Specification:
                    <div>$ExtraInfo</div>
                    <div>Weight: $Weight</div>
                    <div>SKU: $SKU</div>
                    <div>Inventory: $Inventory</div>
                    <div>$ActivePrice.Nice</div>
                </li>
            </ul>
        <% end_loop %>
        <div style="clear:both">
            Quantity: <input type="text" name="Quantity" />
        </div>
        <input type="submit" value="add to cart" />
    </form>

<% end_with %>