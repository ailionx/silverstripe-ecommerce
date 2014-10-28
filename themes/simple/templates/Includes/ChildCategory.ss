<div style="margin-left: 1rem;">
    <a href="$topLink$Link">[$getAncestorCategories($Name).count]: $Name</a>
    <% loop $ChildCategories %>
        <% include ChildCategory topLink=$topLink %>
    <% end_loop %>
</div>