<template>
    <div class="list">
        <ul>
            <!-- {{list}} -->
            <li
                v-for="item in list"
                :class="{ active: item.parent_id === currentId }"
                @click="selectSession(item.parent_id,currentId)"
            >
                <p class="name">{{item.name}}</p>
            </li>
        </ul>
    </div>
</template>

<script>
// import { actions } from "../../store";

export default {
    computed: {
        list() {
            let list = this.$store.getters.list_show;
            // console.log("list", list);
            // let session = this.$store.getters.sessions;
            // let filterKey = this.$store.getters.filterKey;
            // return session.filter((user) => name.includes(filterKey));
            return list;
        },
        currentId() {
            console.log("curid: ", this.$store.getters.currentSessionId);
            return this.$store.getters.currentSessionId;
        },
    },
    methods: {
        selectSession(id, currentId) {
            if (id === currentId) {
                return;
            }
            console.log("id: ", id);
            this.$store.dispatch("selectAction", id);
        },
    },
};
</script>
<style lang="scss" scoped>
.list {
    overflow-y: scroll;
    // li {
    //     padding: 12px 15px;
    //     border-bottom: 1px solid #292c33;
    //     cursor: pointer;
    //     transition: background-color 0.1s;

    //     &:hover {
    //         background-color: rgba(255, 255, 255, 0.03);
    //     }
    //     &.active {
    //         background-color: rgba(255, 255, 255, 0.1);
    //     }
    // }
    // .avatar,
    // .name {
    //     vertical-align: middle;
    // }
    // .avatar {
    //     border-radius: 2px;
    // }
    // .name {
    //     display: inline-block;
    //     margin: 0 0 0 15px;
    // }
}
</style>