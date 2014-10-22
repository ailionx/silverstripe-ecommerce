<div>
    <h3>My Orders</h3>
    <table>
        <tr>
            <th>Reference</th>
            <th>Create Time</th>
            <th>Products</th>
            <th>Status</th>
        </tr>
        <% loop MyOrders %>
            <tr>
                <td>$Reference</td>
                <td>$Created</td>
                <td>
                    <% loop Items %>
                        <div>$Name</div>
                    <% end_loop %>
                </td>
                <td>$Status</td>
            </tr>        
        <% end_loop %>
    </table>
</div>
