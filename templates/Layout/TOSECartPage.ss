<% with $Cart %>
    <% if $cartEmpty %>
        <div>No items in cart yet</div>
        <a href="$Top.get_page_link('TOSECategoryPage')"><button>Go Shopping</button></a>
    <% else %>
        <% include CartPanel CartLink=$Top.get_page_link('TOSECartPage') %>
        <a href='{$Top.get_page_link('TOSECartPage')}clearCart'><button>Clear Cart</button></a>
        <a href="$Top.get_page_link('TOSECheckoutPage')"><button>Checkout</button></a>
    <% end_if %>
<% end_with %>