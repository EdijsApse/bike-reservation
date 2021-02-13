<template>
    <nav class="navbar navbar-expand-lg justify-content-end">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <router-link to="/" tag="li"><a class="nav-link">Reservation list</a></router-link>
                <router-link to="/bikes" tag="li"><a class="nav-link">Bikes</a></router-link>
                <router-link to="/employees" tag="li"><a class="nav-link">Employees</a></router-link>
            </ul>
        </div>
    </nav>

</template>