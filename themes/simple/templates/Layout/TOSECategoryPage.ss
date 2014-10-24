<div style="float: left">
    <% include CategoryBar %>
</div>

<div>
    <% with $Category %>
        $getAncerstorCategoriesID
    <% end_with %>
</div>