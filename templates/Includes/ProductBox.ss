<a href="$Link" >
    <div style="float: left; margin: 20px; height: 180px;">
        <div><img src="$DefaultImage.Filename" style="width: 100px;"></div>
        <div><h4>$Name</h4></div>
        <% if $DefaultPrice %>
            <div>$DefaultPrice.Nice</div>
        <% end_if %>
    </div>
</a>