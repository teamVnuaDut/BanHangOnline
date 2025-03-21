( function( blocks, element, serverSideRender, blockEditor, components ) {

    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var RichText = blockEditor.RichText;
    var ServerSideRender = serverSideRender;

    var InspectorControls = blockEditor.InspectorControls;
    var Fragment = element.Fragment;

    var TextControl = components.TextControl;
    var Panel = components.Panel;
    var PanelBody = components.PanelBody;
    var PanelRow = components.PanelRow;

    var sIcon = el('svg', { width: 20, height: 20 },
        el('path', { fill: "#7f54b3", d: "M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" } )
    );

    blocks.updateCategory('aws', { icon: sIcon });

    var blockStyle = {
        backgroundColor: '#900',
        color: '#fff',
        padding: '20px',
    };

    blocks.registerBlockType( 'advanced-woo-search/search-block', {
        apiVersion: 2,
        title: 'Search Form',
        description: 'Advanced Woo Search form block inserts plugin search form into the page.',
        icon: sIcon,
        category: 'aws',
        example: {},
        edit: function( props ) {

            var blockProps = blockEditor.hasOwnProperty('useBlockProps') ? blockEditor.useBlockProps() : null;

            return (
                el( Fragment, {},
                    el( InspectorControls, {},
                        el( PanelBody, { title: 'Search Form Settings', initialOpen: true },

                            /* Text Field */
                            el( PanelRow, {},
                                el( TextControl,
                                    {
                                        label: 'Placeholder text',
                                        onChange: ( value ) => {
                                            props.setAttributes( { placeholder: value } );
                                        },
                                        value: props.attributes.placeholder
                                    }
                                )
                            ),

                        ),

                    ),
                    el(
                        'div',
                        blockProps,
                        el( ServerSideRender, {
                            block: 'advanced-woo-search/search-block',
                            attributes: props.attributes,
                        } )
                    )
                )

            );


        },
        save: function( props ) {
            return null;
        },
    } );
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.serverSideRender,
    window.wp.blockEditor,
    window.wp.components,
) );