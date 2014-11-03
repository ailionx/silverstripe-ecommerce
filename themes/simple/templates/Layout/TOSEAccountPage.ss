<div>
    <h3>My Orders</h3>
    <table>
        <tr>
            <th>Reference</th>
            <th>Products</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Create Time</th>
        </tr>
        <% loop MyOrders %>
            <tr>
                <td>$Reference</td>
                <td>
                    <% loop Items %>
                        <div>$Name</div>
                    <% end_loop %>
                </td>
                <td>$TotalPrice.Nice</td>
                <td>$Status</td>
                <td>$Created</td>
            </tr>        
        <% end_loop %>
    </table>
</div>
