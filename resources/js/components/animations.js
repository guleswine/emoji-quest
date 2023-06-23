
const building = [
    {transform: 'rotate(-20.0deg) scale(1)', backgroundImage: 'url("/open_emoji/lite_colored/hammer.png")'},
    {transform: 'rotate(20.0deg) scale(1)', backgroundImage: 'url("/open_emoji/lite_colored/hammer.png")'},
];
const hero_steps = [
    {transform: 'scale(0.5)',backgroundImage: 'url("/open_emoji/lite_colored/footprints.png")',backgroundSize: '2rem'},
    {transform: 'scale(1)',backgroundImage: 'url("/open_emoji/lite_colored/footprints.png")',backgroundSize: '2rem'},
];
const hero_show = [
    {transform: 'scale(0.5)'},
    {transform: 'scale(1)'},
];

const unit_attaked = [
    {transform: 'rotate(-30.0deg)'},
    {transform: 'rotate(30.0deg)'},
    {transform: 'rotate(-15.0deg)'},
    {transform: 'rotate(15.0deg)'},
];

const unit_use_defence = [
    {transform: 'scale(1)'},
    {transform: 'scale(1.5)'},
];

export default {
    building,
    hero_steps,
    hero_show,
    unit_attaked,
    unit_use_defence
};
