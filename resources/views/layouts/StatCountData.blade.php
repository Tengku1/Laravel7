<div class="col-12 row">
    @if (Auth::user()->roles[0]=="Master")
    <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">Users</div>
            <div class="card-body text-center">
                {{$user->count()}}
                <i class="fa fa-user"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">Branch</div>
            <div class="card-body text-center">
                {{$branch->count()}}
                <i class="fa fa-code-fork"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1 ">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">Products</div>
            <div class="card-body text-center">
                {{$stock->count()}}
                <i class="fa fa-shopping-cart"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">History Buy</div>
            <div class="card-body text-center">
                {{$historyBuy->count()}}
                <i class="fa fa-history"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">Stock</div>
            <div class="card-body text-center">
                {{$historyBuy->count()}}
                <i class="fa fa-history"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">History Sell</div>
            <div class="card-body text-center">
                {{$historySell->count()}}
                <i class="fa fa-history"></i>
            </div>
        </div>
    </div>
    @else
    <div class="col-4 dashboardDatabase float-left px-1 ">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">Products</div>
            <div class="card-body text-center">
                {{$stock->count()}}
                <i class="fa fa-shopping-cart"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">History Buy</div>
            <div class="card-body text-center">
                {{$historyBuy->count()}}
                <i class="fa fa-history"></i>
            </div>
        </div>
    </div>
    <div class="col-4 dashboardDatabase float-left px-1">
        <div class="card col-md-12 dashboardCount">
            <div class="card-header">History Sell</div>
            <div class="card-body text-center">
                {{$historySell->count()}}
                <i class="fa fa-history"></i>
            </div>
        </div>
    </div>
    @endif
</div>