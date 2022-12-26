module.exports = {
    plugins: [
        {
            name: 'preset-default',
            params: {
                overrides: {
                    removeAttrs: 'fill'
                },
            },
        },
        {
            name: "removeAttrs",
            params: {
                attrs: "(fill|stroke)"
            }
        }
    ],
};