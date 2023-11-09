<x-splade-data remember="menu" default="{ tab1: false, tab2: false, tab3: false }">
    <aside v-show="data.tab1">
        11111111111
    </aside>

    <aside v-show="data.tab2">
        222222222222
    </aside>

    <aside v-show="data.tab3">
        33333333333
    </aside>
    <button @click.prevent="data.tab3 = true">Accept</button>
</x-splade-data>

<x-splade-data remember="cookie-popup" local-storage default="{ accepted: false }">
    <div v-if="!data.accepted">
        <h1>Cookie warning</h1>
    </div>

    <button @click.prevent="data.accepted = true">Accept</button>
</x-splade-data>

<x-splade-data store="navigation" default="{ opened: false }" />

<!-- other elements... -->

<button @click.prevent="navigation.opened = true">
    Open Navigation
</button>
<nav v-show="navigation.opened">
    eeeeeeeeeeeee
</nav>
