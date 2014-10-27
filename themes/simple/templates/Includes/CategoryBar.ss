<div>
    <h3>First Solution</h3>
    <% loop RootCategories %>
    <div>
        <% loop get_descendant_categories($Name) %>
            <div style="margin-left: {$Level}rem;">
                <a href="$Top.getEcommerceRootPageLink/$Link">[$Level]: {$Name}</a>
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