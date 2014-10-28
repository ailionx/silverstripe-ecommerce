<div>
    <h3>First Solution</h3>
    <% loop RootCategories %>
    <div>
        <% loop getDescendantCategories($Name) %>
            <div style="margin-left: {$Level}rem;">
                <a href="{$Top.Link}$Link">[$Level]: {$Name}</a>
            </div>
        <% end_loop %>
    </div>
    <% end_loop %>
</div>

<div>
    <h3>Second Solution</h3>
    <% loop RootCategories %>
        <% include ChildCategory topLink=$Top.Link %>
    <% end_loop %>
</div>