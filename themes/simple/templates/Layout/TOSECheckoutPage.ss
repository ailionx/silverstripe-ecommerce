<% with $Cart %>
    <% include CartPanel CartLink=$Top.getCartLink,CurrencySymbol=$Top.CurrentCurrencySymbol %>
<% end_with %>
<div>
    $orderForm
</div>