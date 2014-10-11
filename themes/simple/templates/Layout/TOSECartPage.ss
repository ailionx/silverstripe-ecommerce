<% with $Cart %>
    <% if $cartEmpty %>
        <div>No items in cart yet</div>
        <a href='$Top.getEcommerceRootPageLink'><button>Go Shopping</button></a>
    <% else %>
        <% include ShowCart %>
    <% end_if %>
<% end_with %>