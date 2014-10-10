<nav class="primary">
	<span class="nav-open-button">²</span>
	<ul>
            <% loop $Children %>
Shawn
                    <li class="$LinkingMode"><a href="$Link">$Title</a></li>
            <% end_loop %>
	</ul>
</nav>
