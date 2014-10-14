<% with $Cart %>
    <% if $cartEmpty %>
        <div>No items in cart yet</div>
        <a href='$Top.getEcommerceRootPageLink/product/'><button>Go Shopping</button></a>
    <% else %>
        <% include ShowCart CartLink=$Top.getCartLink,CurrencySymbol=$Top.CurrentCurrencySymbol %>
    <% end_if %>
<% end_with %>

<div id="customer-info" style="margin-top: 30px;">
    <h3>Customer Information</h3>
    <div>Name: $CustomerName</div>
    <div>Email: $CustomerEmail</div>
    <div>Phone: $CustomerPhone</div>
</div>
<div id="shipping-info" style="margin-top: 30px;">
    <h3>Shipping Information</h3>
    <div>First Name: $ShippingFirstName</div>
    <div>SurName: $ShippingSurName</div>
    <div>Phone: $ShippingPhone</div>
    <div>Street Number: $ShippingStreetNumber</div>
    <div>Street Name: $ShippingStreetName</div>
    <div>Suburb: $ShippingSuburb</div>
    <div>City: $ShippingCity</div>
    <div>Region: $ShippingRegion</div>
    <div>Country: $ShippingCountry</div>
    <div>Postal Code: $ShippingPostCode</div>
</div>
Symbol: 