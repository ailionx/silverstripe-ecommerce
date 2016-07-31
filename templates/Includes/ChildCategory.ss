<div style="margin-left: 1rem;">
    [$AllProducts.count]<a href="$topLink$Link">$Name</a>

            <!--
    <div style="margin-left: 1rem;">
        [$Products.count]<a href="javascript:void(0)">Other</a>
    </div>
            -->

    <% loop $ChildCategories %>
        <% include ChildCategory topLink=$topLink %>
    <% end_loop %>
</div>