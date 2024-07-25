<nav id="sidebar" class="sidebar js-sidebar">
	<div class="sidebar-content js-simplebar">
		<a class="sidebar-brand" href="{{ route('admin.dashboard')}}">
			<span class="align-middle" style="font-size:28px;font-style: italic;">Amana</span>
		</a>

		<ul class="sidebar-nav">
			<li class="sidebar-header">
				Main Pages
			</li>

			<li class="sidebar-item active">
				<a class="sidebar-link" href="{{ url('admin.dashboard')}}">
					<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
				</a>
			</li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="{{ url('journals.index')}}">
					<i class="align-middle" data-feather="user"></i> <span class="align-middle">Order</span>
				</a>
			</li>



			<li class="sidebar-item">
				<a href="#product" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
					<span class=""> </span>
					<i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Product</span>
					<span class="sidebar-badge badge"><i class="align-middle" data-feather="chevron-down"></i> </span>
				</a>

				<ul id="product" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar"
					style="margin-left:15px;">
					<li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.dashboard.index') }}"><i class="align-middle"
								data-feather="corner-down-right"></i> All Products <span
								class="sidebar-badge badge bg-primary">0</span></a> </li>
					<li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.dashboard.create') }}"><i
								class="align-middle" data-feather="corner-down-right"></i> Add Product</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="/pages-reset-password"><i
								class="align-middle" data-feather="corner-down-right"></i>Out Of Stock <span
								class="sidebar-badge badge bg-primary">0</span></a></li>
				</ul>
			</li>



			<li class="sidebar-item">
				<a class="sidebar-link" href="pages-blank.html">
					<i class="align-middle" data-feather="shopping-bag"></i> <span class="align-middle">All
						Vendor</span>
				</a>
			</li>


			<li class="sidebar-item">
				<a class="sidebar-link" href="pages-blank.html">
					<i class="align-middle" data-feather="dollar-sign"></i> <span
						class="align-middle">Transactions</span>
				</a>
			</li>


			<li class="sidebar-item">
				<a href="#auth" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
					<span class=""> </span>
					<i class="align-middle" data-feather="users"></i> <span class="align-middle">Customer</span> <span
						class="sidebar-badge badge"><i class="align-middle" data-feather="chevron-down"></i> </span>
				</a>
				<ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar"
					style="margin-left:15px;">
					<li class="sidebar-item"><a class="sidebar-link" href="/pages-sign-in"><i class="align-middle"
								data-feather="corner-down-right"></i> All Customer
							<span class="sidebar-badge badge bg-primary">0</span></a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="/pages-sign-up"><i class="align-middle"
								data-feather="corner-down-right"></i> Add New Customer</a></li>
				</ul>
			</li>


			<li class="sidebar-header"> Extra Pages </li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="charts-chartjs.html">
					<i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Charts</span>
				</a>
			</li>

			<li class="sidebar-item">
				<a class="sidebar-link" href="maps-google.html">
					<i class="align-middle" data-feather="map"></i> <span class="align-middle">Maps</span>
				</a>
			</li>
		</ul>

		<div class="sidebar-cta text-white" style="text-align:center;">
			Back To The Panel
		</div>
	</div>
</nav>