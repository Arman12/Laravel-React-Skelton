import React from "react";
import { Outlet  } from "react-router-dom";


// __ components __ //
import Header from "./Header";
import Footer from "./Footer"

const Layout = () => {
	return (
		<>
			{/* {!loader || <Loader />} */}
			<Header />
			<Outlet />
			<Footer />
		</>
	);
};

export default Layout;
