<div>
    <h3>First Solution</h3>
    <% loop RootCategories %>
    <div>
        <% loop get_descendant_categories($Name) %>
            <div style="margin-left: {$Level}rem;">
                [$Level]: {$Name}
            </div>
        <% end_loop %>
    </div>
    <% end_loop %>
</div>

<div>
    <h3>Second Solution</h3>
    <% loop RootCategories %>
        <% include ChildCategory %>
    <% end_loop %>
</div>