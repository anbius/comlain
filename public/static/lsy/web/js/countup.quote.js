var options = {
    useEasing: true,
    useGrouping: true,
    separator: ',',
    decimal: '.',
    prefix: '',
    suffix: ''
};
var demo1 = new CountUp('shuju1', 0, 7832, 0, 2.5, options);
var demo2 = new CountUp('shuju2', 0, 7162, 0, 2.5, options);
var demo3 = new CountUp('shuju3', 0, 5064, 0, 2.5, options);
var demo4 = new CountUp('shuju4', 0, 10118, 0, 2.5, options);
$(window)['scroll'](function () {
    if ($(window)['scrollTop']() >= 850) {
        demo1['start']();
        demo2['start']();
        demo3['start']();
        demo4['start']()
    }
});
