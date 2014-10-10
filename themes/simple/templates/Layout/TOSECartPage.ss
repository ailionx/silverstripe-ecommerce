<% with $Cart %>
    <% if $cartEmpty %>
        <div>No items in cart yet</div>
        <a href='$Top.getEcommerceRootPageLink'><button>Go Shopping</button></a>
    <% else %>
        <% loop $CartItems %>
            <div>
                <div style="background-image: url('$Product.DefaultImage.Filename'); background-size:contain; width: 80px; height: 80px; background-repeat: no-repeat;"></div>
                <div>Product Name: $Product.Name</div>
                <% with $Spec %>
                    <% if $ExtraInfo %><div>Specification: $ExtraInfo</div><% end_if %>
                    <% with $Currency %>
                        <div>$Currency: $get_current_currency_symbol$priceFormatted</div>
                    <% end_with %>
                <% end_with %>
                <form method="post" action="{$Top.Link}updateItem">
                <div>Quantity: <input type="number" value="$Quantity" name="Quantity" style="width: 40px" /></div>
                <div>SubTotal: $Top.CurrentCurrencySymbol$subTotalPriceFormatted</div>
                <input type="hidden" value="$Product.ID" name="ProductID" />
                <input type="hidden" value="$Spec.ID" name="SpecID" />
                <button type="submit">update</button>
                </form>
                <a href="$Top.Link()deleteItem?ProductID=$ProductID&SpecID=$SpecID"><button style="float: none;">delete</button></a>
            </div>
        <% end_loop %>
        <div>Total Price: $Top.CurrentCurrencySymbol$totalPriceFormatted</div>
        <a href='$Top.Link/clearCart'><button>Clear Cart</button></a>
        <a href="$Top.getEcommerceRootPageLink/checkout"><button>Checkout</button></a>
    <% end_if %>
<% end_with %>