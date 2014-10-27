<div style="margin-left: 1rem;">
    <a href="$Link">[$get_ancestor_categories($Name).count]: $Name</a>
    <% loop $ChildCategories %>
        <% include ChildCategory %>
    <% end_loop %>
</div>