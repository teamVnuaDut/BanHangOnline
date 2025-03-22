import { Icon, Sidebar, Card, Heading } from "../../components";
import { __ } from '@wordpress/i18n';

const Homepage = () => {
    const cardLists = [
        {
            iconSvg: <Icon icon="site" />,
            heading: __('Site Identity', 'blossom-shop'),
            buttonText: __('Customize', 'blossom-shop'),
            buttonUrl: cw_dashboard.custom_logo
        },
        {
            iconSvg: <Icon icon="colorsetting" />,
            heading: __("Color Settings", 'blossom-shop'),
            buttonText: __('Customize', 'blossom-shop'),
            buttonUrl: cw_dashboard.colors
        },
        {
            iconSvg: <Icon icon="layoutsetting" />,
            heading: __("Layout Settings", 'blossom-shop'),
            buttonText: __('Customize', 'blossom-shop'),
            buttonUrl: cw_dashboard.layout
        },
        {
            iconSvg: <Icon icon="instagramsetting" />,
            heading: __("Instagram Settings", 'blossom-shop'),
            buttonText: __('Customize', 'blossom-shop'),
            buttonUrl: cw_dashboard.instagram
        },
        {
            iconSvg: <Icon icon="frontpagesetting" />,
            heading: __("Front Page Settings"),
            buttonText: __('Customize', 'blossom-shop'),
            buttonUrl: cw_dashboard.general
        },
        {
            iconSvg: <Icon icon="footersetting" />,
            heading: __('Footer Settings', 'blossom-shop'),
            buttonText: __('Customize', 'blossom-shop'),
            buttonUrl: cw_dashboard.footer
        }
    ];

    const proSettings = [
        {
            heading: __('Header Layouts', 'blossom-shop'),
            para: __('Choose from different unique header layouts.', 'blossom-shop'),
            buttonText: __('Learn More', 'blossom-shop'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('Multiple Layouts', 'blossom-shop'),
            para: __('Choose layouts for blogs, banners, posts and more.', 'blossom-shop'),
            buttonText: __('Learn More', 'blossom-shop'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('Multiple Sidebar', 'blossom-shop'),
            para: __('Set different sidebars for posts and pages.', 'blossom-shop'),
            buttonText: __("Learn More", 'blossom-shop'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('Top Bar Settings', 'blossom-shop'),
            para: __('Show a notice or newsletter at the top.', 'blossom-shop'),
            buttonText: __('Learn More', 'blossom-shop'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Boost your website performance with ease.', 'blossom-shop'),
            heading: __('Performance Settings', 'blossom-shop'),
            buttonText: __('Learn More', 'blossom-shop'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Choose typography for different heading tags.', 'blossom-shop'),
            heading: __('Typography Settings', 'blossom-shop'),
            buttonText: __('Learn More', 'blossom-shop'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Import the demo content to kickstart your site.', 'blossom-shop'),
            heading: __('One Click Demo Import', 'blossom-shop'),
            buttonText: __('Learn More', 'blossom-shop'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Get advanced product sections in the homepage.', 'blossom-shop'),
            heading: __('Advanced WooCommerce', 'blossom-shop'),
            buttonText: __('Learn More', 'blossom-shop'),
            buttonUrl: cw_dashboard?.get_pro
        },
    ];

    const sidebarSettings = [
        {
            heading: __('We Value Your Feedback!', 'blossom-shop-pro'),
            icon: "star",
            para: __("Your review helps us improve and assists others in making informed choices. Share your thoughts today!", 'blossom-shop-pro'),
            imageurl: <Icon icon="review" />,
            buttonText: __('Leave a Review', 'blossom-shop-pro'),
            buttonUrl: cw_dashboard.review
        },
        {
            heading: __('Knowledge Base', 'blossom-shop-pro'),
            para: __("Need help using our theme? Visit our well-organized Knowledge Base!", 'blossom-shop-pro'),
            imageurl: <Icon icon="documentation" />,
            buttonText: __('Explore', 'blossom-shop-pro'),
            buttonUrl: cw_dashboard.docmentation
        },
        {
            heading: __('Need Assistance? ', 'blossom-shop-pro'),
            para: __("If you need help or have any questions, don't hesitate to contact our support team. We're here to assist you!", 'blossom-shop-pro'),
            imageurl: <Icon icon="supportTwo" />,
            buttonText: __('Submit a Ticket', 'blossom-shop-pro'),
            buttonUrl: cw_dashboard.support
        }
    ];

    return (
        <>
            <div className="customizer-settings">
                <div className="cw-customizer">
                    <div className="video-section">
                        <div className="cw-settings">
                            <h2>{__('Blossom Shop Tutorial', 'blossom-shop')}</h2>
                        </div>
                        <iframe src="https://www.youtube.com/embed/5qTwEdHx0sw" title={__( 'How To Create An Online Store With WordPress in 2023 | Blossom Shop', 'blossom-shop' )} frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerPolicy="strict-origin-when-cross-origin" allowFullScreen></iframe>
                    </div>
                    <Heading
                        heading={__( 'Quick Customizer Settings', 'blossom-shop' )}
                        buttonText={__( 'Go To Customizer', 'blossom-shop' )}
                        buttonUrl={cw_dashboard?.customizer_url}
                        openInNewTab={true}
                    />
                    <Card
                        cardList={cardLists}
                        cardPlace='customizer'
                        cardCol='three-col'
                    />
                    <Heading
                        heading={__( 'More features with Pro version', 'blossom-shop' )}
                        buttonText={__( 'Go To Customizer', 'blossom-shop' )}
                        buttonUrl={cw_dashboard?.customizer_url}
                        openInNewTab={true}
                    />
                    <Card
                        cardList={proSettings}
                        cardPlace='cw-pro'
                        cardCol='two-col'
                    />
                    <div className="cw-button">
                        <a href={cw_dashboard?.get_pro} target="_blank" className="cw-button-btn primary-btn long-button">{__('Learn more about the Pro version', 'blossom-shop')}</a>
                    </div>
                </div>
                <Sidebar sidebarSettings={sidebarSettings} openInNewTab={true} />
            </div>
        </>
    );
}

export default Homepage;