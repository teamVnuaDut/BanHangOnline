import { Icon } from "../../components";
import { single, homepage, archive } from "../../components/images"
import { __ } from "@wordpress/i18n";

const StarterSites = () => {
    return (
        <>
            <div className="starter-sites">
                <div className="image-wrapper">
                    <div className="image">
                        <img src={single} alt={__( 'Demo image', 'blossom-shop' )} />
                        <div className="reverse-image">
                            <img src={single} alt={__( 'Demo reverse image', 'blossom-shop' )} />
                        </div>
                    </div>
                    <div className="image">
                        <img src={homepage} alt={__( 'Demo image', 'blossom-shop' )} />
                        <div className="reverse-image">
                            <img src={homepage} alt={__( 'Demo reverse image', 'blossom-shop' )} />
                        </div>
                    </div>
                    <div className="image">
                        <img src={archive} alt={__( 'Demo image', 'blossom-shop' )} />
                        <div className="reverse-image">
                            <img src={archive} alt={__( 'Demo reverse image', 'blossom-shop' )} />
                        </div>
                    </div>
                </div>
                <div className="text-wrapper">
                    <h2>{__('One Click Demo Import', 'blossom-shop')}</h2>
                    <p dangerouslySetInnerHTML={{__html: sprintf(__('Get started effortlessly! Use our one-click demo import feature to set up your site instantly with all the sample data and settings. Please note that importing demo content will overwrite your existing site content and settings. %s Not recommended if you have existing content. %s', 'blossom-shop'), '<b>', '</b>')}} />
                    <div className="cw-button">
                        <a href={cw_dashboard.get_pro} target="_blank" className="cw-button-btn primary-btn">
                            {__('Get Starter Sites', 'blossom-shop')} <Icon icon="arrow" />
                        </a>
                    </div>
                </div>
            </div>
        </>
    );
}

export default StarterSites;