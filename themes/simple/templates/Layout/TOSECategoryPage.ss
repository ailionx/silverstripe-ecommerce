<div style="float: left; width: 30%;">
    <% include CategoryBar %>
</div>

<div style="float: left; width: 70%;">

    <% if ShowDefault %>
        <% loop $DefaultProducts %>
            <% include ProductBox CurrencyName=$Top.CurrentCurrencyName, CurrenySymbol=$Top.CurrentCurrencySymbol %>
        <% end_loop %>
    <% end_if %>
    <% if $Category %>
        <% with $Category %>
            <h3>$Name</h3>
            <% loop $AllProducts %>
                <% include ProductBox CurrencyName=$Top.CurrentCurrencyName, CurrenySymbol=$Top.CurrentCurrencySymbol %>
            <% end_loop %>
        <% end_with %>
    <% end_if %>
</div>