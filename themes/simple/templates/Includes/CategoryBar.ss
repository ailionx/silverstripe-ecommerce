<div>
    <h3>Category</h3>
    <% loop RootCategories %>
        <% include ChildCategory topLink=$Top.Link %>
    <% end_loop %>
</div>