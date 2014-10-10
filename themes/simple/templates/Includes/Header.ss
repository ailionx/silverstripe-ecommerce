<header class="header" role="banner">
	<div class="inner">                
            <div style="float:right; color: #fff">
                <% if $isCustomerLogin %>
                    User: $MemberName<br>
                <% end_if %>
                <a style="float:right;" href="$getEcommerceRootPageLink/$getLogInOut"><button>$getLogInOut</button></a>
            </div>
            <div class="unit size4of4 lastUnit">
                    <a href="{$BaseHref}ecommerce" class="brand" rel="home">
                            <h1>$SiteConfig.Title</h1>
                            <% if $SiteConfig.Tagline %>
                            <p>$SiteConfig.Tagline</p>
                            <% end_if %>
                    </a>
                    <% if $SearchForm %>
                            <span class="search-dropdown-icon">L</span>
                            <div class="search-bar">
                                    $SearchForm
                            </div>
                    <% end_if %>
                    <% include Navigation %>
            </div>
	</div>
</header>
