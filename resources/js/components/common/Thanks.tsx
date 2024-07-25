import React from "react"; 
import Thank from './images/ThankYou.png'
const Thanks = () => {
	return (
		<>
			 <div className="ThankYou_Wrap">
                <div className="thank_inner">
				       <img src={Thank} alt="ThankYou" />
						<h1>Thank you!</h1>
						<p>for contacting us, we will reply promptly</p> 
						<p>once your message is received. </p>
				</div>
			 </div>
		</>
	);
};

export default Thanks;
