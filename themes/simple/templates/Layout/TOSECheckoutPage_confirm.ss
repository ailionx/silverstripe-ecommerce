<% with $Cart %>
    <% if $cartEmpty %>
        <div>No items in cart yet</div>
        <a href='$Top.getEcommerceRootPageLink/product/'><button>Go Shopping</button></a>
    <% else %>
        <% include ShowCart CartLink=$Top.getCartLink %>
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

<% if $NeedInvoice %>
    <div id="billing-info" style="margin-top: 30px;">
        <h3>Billing Information</h3>
        <div>First Name: $BillingFirstName</div>
        <div>SurName: $BillingSurName</div>
        <div>Phone: $BillingPhone</div>
        <div>Street Number: $BillingStreetNumber</div>
        <div>Street Name: $BillingStreetName</div>
        <div>Suburb: $BillingSuburb</div>
        <div>City: $BillingCity</div>
        <div>Region: $BillingRegion</div>
        <div>Country: $BillingCountry</div>
        <div>Postal Code: $BillingPostCode</div>
    </div>
<% end_if %>

<div>
    <div id="message" style="margin-top: 30px;">
        <h3>Message</h3>
        $Comments
    </div>
</div>

<div>
    <div id="payment-method" style="margin-top: 30px;">
        <h3>Payment Method</h3>
        $PaymentMethod
    </div>
</div>

<div class="action">
    <a href="$Link">
        <button>Go Back</button>
    </a>
    <a href="{$Link}doPay">
        <button>Place Order</button>
    </a>
</div>
