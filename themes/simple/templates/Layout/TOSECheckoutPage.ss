<% with $Cart %>
    <% include ShowCart CartLink=$Top.getCartLink,CurrencySymbol=$Top.CurrentCurrencySymbol %>
<% end_with %>
<div>
    $orderForm
</div>