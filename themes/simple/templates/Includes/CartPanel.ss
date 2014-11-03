<% loop $getCartItems %>
    <div>
        <div style="background-image: url('$Product.DefaultImage.Filename'); background-size:contain; width: 80px; height: 80px; background-repeat: no-repeat;"></div>
        <div>Product Name: $Product.Name</div>
        <% with $Spec %>
            <% if $ExtraInfo %><div>Specification: $ExtraInfo</div><% end_if %>
            <% with $ActivePrice %>
                <div>Price: $Nice</div>
            <% end_with %>
        <% end_with %>
        <form method="post" action="{$CartLink}updateQuantity">
        <div>Quantity: 
            <input type="number" value="$Quantity" name="Quantity" style="width: 40px" />
        </div>
        
        <div>SubTotal: $subTotalPrice.Nice</div>
        <input type="hidden" value="$Spec.ID" name="SpecID" />
        <button type="submit">update</button>
        </form>
        <% if not $QuantityReachMin %>
            <a href='{$CartLink}quantityMinus?SpecID=$SpecID'><button style='float:none'>-</button></a>
        <% end_if %>
        <% if not $QuantityReachMax %>
            <a href='{$CartLink}quantityPlus?SpecID=$SpecID'><button style='float:none'>+</button></a>
        <% end_if %>
        <a href="{$CartLink}removeItem?SpecID=$SpecID"><button style="float: none;">delete</button></a>
    </div>
<% end_loop %>
<div>Total Price: $totalPrice.Nice</div>