<% with $Cart %>
    <% include ShowCart CartLink=$Top.getCartLink,CurrencySymbol=$Top.CurrentCurrencySymbol %>
<% end_with %>
<div>
    $orderForm
</div>
<a href="$Top.getEcommerceRootPageLink/checkout/confirm"><button>Next</button></a>