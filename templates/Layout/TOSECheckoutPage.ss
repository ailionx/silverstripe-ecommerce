<% with $Cart %>
    <% include CartPanel CartLink=$Top.get_page_link('TOSECartPage') %>
<% end_with %>
<div>
    $orderForm
</div>