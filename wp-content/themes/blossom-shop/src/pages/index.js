import { useState } from 'react';
import { Icon, Tab } from '../components';
import FreePro from './FreePro';
import Homepage from "./Home";
import Offers from './Offers';
import UsefulPlugins from './UsefulPlugins';
import FAQ from './FAQ';
import StarterSites from './StarterSites';
import { __ } from '@wordpress/i18n';

function Dashboard() {
    const [activeTabTitle, setActiveTabTitle] = useState('Home');

    const tabsData = [
        {
            title: __( 'Home', 'blossom-shop' ),
            icon: <Icon icon="home" />,
            content: <Homepage />
        },
        {
            title: __( 'Starter Sites', 'blossom-shop' ),
            icon: <Icon icon="globe" />,
            content: <StarterSites />
        },
        {
            title: __( 'Free vs Pro', 'blossom-shop' ),
            icon: <Icon icon="freePro" />,
            content: <FreePro />
        },
        {
            title: __( 'Offers', 'blossom-shop' ),
            icon: <Icon icon="offers" />,
            content: <Offers />
        },
        {
            title: __( 'FAQs', 'blossom-shop' ),
            icon: <Icon icon="support" />,
            content: <FAQ />
        },
        {
            title: __( 'Useful Plugins', 'blossom-shop' ),
            icon: <Icon icon="plugins" />,
            content: <UsefulPlugins />
        }
    ];

    const handleTabChange = (title) => {
        setActiveTabTitle(title);
    };

    return (
        <>
            <Tab
                tabsData={tabsData}
                onChange={handleTabChange}
                activeTabTitle={activeTabTitle}
            />
        </>
    );
}

export default Dashboard;