<div style="margin-left: 1rem;">
    [$get_ancestor_categories($Name).count]: $Name
    <% loop $ChildCategories %>
        <% include ChildCategory %>
    <% end_loop %>
</div>