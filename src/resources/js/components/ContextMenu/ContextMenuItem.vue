<template>
    <li class="context-menu-item">
        <slot />
    </li>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.context-menu-item {
    display: block;
    position: relative;
    padding: 2px 2px;
    background: #fff;
    border-radius: 0;
    border-color: #000;
    color: #000;
    text-decoration: none;
    font-size: 15px;
    width: 100%;
    text-align: left;
    cursor: pointer;
    padding: 8px;

    &:hover,
    &:focus {
        background: rgb(184, 184, 184);
        color: #000;
        outline: none;
    }
}
</style>

