<section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
        <li class="{{ Route::is('home') ? 'active' : '' }}">
            <a href="{{route('home')}}">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                <span class="pull-right-container">
            </span>
            </a>
        </li>

        <li class="treeview
            {{ Route::is('products.*') ? 'active' : '' }} ||
            {{ Route::is('products-categories.*') ? 'active' : '' }}
        ">
            <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>Products</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('products-categories.*') ? 'active' : '' }}">
                    <a href="{{route('products-categories.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Category
                    </a>
                </li>
                <li class="{{ Route::is('products.*') ? 'active' : '' }}">
                    <a href="{{route('products.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Products
                    </a>
                </li>
            </ul>
        </li>
        <li class="{{ Route::is('purchase.*') ? 'active' : '' }}">
            <a href="{{route('purchase.index')}}">
                <i class="fa fa-shopping-cart"></i>
                <span>Purchase</span>
                <span class="pull-right-container">
            </span>
            </a>
        </li>
        <li class="{{ Route::is('order.*') ? 'active' : '' }}">
            <a href="{{route('order.index')}}">
                <i class="fa fa-shopping-cart"></i>
                <span>Order</span>
                <span class="pull-right-container">
            </span>
            </a>
        </li>
        <li class="treeview
        {{ Route::is('expense.*') ? 'active' : '' }} ||
            {{ Route::is('expense-categories.*') ? 'active' : '' }}
        ">
            <a href="#">
                <i class="fa fa-dollar"></i>
                <span>Expense</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('expense-categories.*') ? 'active' : '' }}">
                    <a href="{{route('expense-categories.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Category
                    </a>
                </li>
                <li class="{{ Route::is('expense.*') ? 'active' : '' }}">
                    <a href="{{route('expense.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Expense
                    </a>
                </li>
            </ul>
        </li>

        <li class="treeview
        {{ Route::is('accounts.*') ? 'active' : '' }} ||
            {{ Route::is('transactions.*') ? 'active' : '' }}

        ">
            <a href="#">
                <i class="fa fa-money"></i>
                <span>Accounts</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('accounts.*') ? 'active' : '' }}">
                    <a href="{{route('accounts.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Accounts
                    </a>
                </li>
                <li class="{{ Route::is('transactions.*') ? 'active' : '' }}">
                    <a href="{{route('transactions.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Transactions
                    </a>
                </li>
            </ul>
        </li>

        <li class="treeview
        {{ Route::is('supplier.*') ? 'active' : '' }} ||
            {{ Route::is('customer.*') ? 'active' : '' }} ||
            {{ Route::is('employee.*') ? 'active' : '' }}
        ">
            <a href="#">
                <i class="fa fa-users"></i>
                <span>People</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('supplier.*') ? 'active' : '' }}">
                    <a href="{{route('supplier.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Supplier
                    </a>
                </li>
                <li class="{{ Route::is('customer.*') ? 'active' : '' }}">
                    <a href="{{route('customer.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Customer
                    </a>
                </li>
                <li class="{{ Route::is('employee.*') ? 'active' : '' }}">
                    <a href="{{route('employee.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Employee
                    </a>
                </li>
            </ul>
        </li>

        <li class="treeview">
            <a href="#">
                <i class="fa fa-bar-chart"></i>
                <span>Reports</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> Supplier</a></li>
                <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Customer</a></li>
            </ul>
        </li>

        <li class="treeview">
            <a href="#">
                <i class="fa fa-user-o"></i>
                <span>Administration</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> Users </a></li>
                <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Roles</a></li>
                <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Permissions </a></li>
            </ul>
        </li>

        <li class="treeview
            {{ Route::is('outlet.*') ? 'active' : '' }} ||
            {{ Route::is('warehouse.*') ? 'active' : '' }} ||
            {{ Route::is('designation.*') ? 'active' : '' }} ||
            {{ Route::is('unit.*') ? 'active' : '' }}
        ">
            <a href="#">
                <i class="fa fa-gears"></i>
                <span>Settings</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('outlet.*') ? 'active' : '' }}">
                    <a href="{{route('outlet.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Outlet
                    </a>
                </li>
                <li class="{{ Route::is('warehouse.*') ? 'active' : '' }}">
                    <a href="{{route('warehouse.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Warehouse
                    </a>
                </li>
                <li class="{{ Route::is('designation.*') ? 'active' : '' }}">
                    <a href="{{route('designation.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Designation
                    </a>
                </li>
                <li class="{{ Route::is('unit.*') ? 'active' : '' }}">
                    <a href="{{route('unit.index')}}">
                        <i class="fa fa-circle-o"></i>
                        Unit
                    </a>
                </li>
            </ul>
        </li>




    </ul>
</section>