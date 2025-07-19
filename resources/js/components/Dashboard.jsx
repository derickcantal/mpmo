import React, { useEffect, useState } from 'flowbite-react';

import Navbar from './Navbar';
import mpmoLogo from '../../../public/storage/img/logo.png';
import trxLogo from '../../../public/storage/img/trx-logo.png';
import usdtLogo from '../../../public/storage/img/usdt-logo.png';
import eggLogo from '../../../public/storage/img/egg-common.webp';

export default function Dashboard() {
 
  const [user, setUser] = useState(null);

  useEffect(() => {
    setUser(window.user);
  }, []);

  if (!user) return <div className="text-center p-4">Loading...</div>;
  
  return (
    <>
      <Navbar user={user} />
      <div className="gradient-bg dark:bg-gray-800 p-6 pb-20 sm:pb-safe min-h-screen overflow-hidden">
        <div className="max-w-6xl mx-auto space-y-8">
          <h1 className="lg:text-5xl sm:text-3xl font-bold text-pink-600 mb-6 text-center">
            Welcome, {user.fullname}!
          </h1>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
            <div className="bg-white rounded-lg shadow p-5 text-center">
              <img src={mpmoLogo} alt="MPMO Logo" className="mx-auto w-28 h-28 object-contain" />
              <h1 className="text-3xl font-bold text-orange-600">MPMO</h1>
              <p className="mt-2 text-3xl text-yellow-500 font-extrabold">
                {Number(user.mpmo_balance ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}
              </p>
              <p className="text-gray-500 dark:text-gray-400">$MPMO Balance</p>
            </div>

            <div className="bg-white rounded-lg shadow p-5 text-center">
              <img src={trxLogo} alt="TRX Logo" className="mx-auto w-28 h-28 object-contain" />
              <h1 className="text-3xl font-bold text-orange-600">TRX</h1>
              <p className="mt-2 text-3xl text-pink-500 font-extrabold">
                {Number(user.trx_balance ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2 })}
              </p>
              <p className="text-gray-500 dark:text-gray-400">$TRX Balance</p>
            </div>

            <div className="bg-white rounded-lg shadow p-5 text-center">
              <img src={usdtLogo} alt="USDT Logo" className="mx-auto w-28 h-28 object-contain" />
              <h1 className="text-3xl font-bold text-orange-600">USDT</h1>
              <p className="mt-2 text-3xl font-extrabold text-gray-600">0.00</p>
              <p className="text-gray-500 dark:text-gray-400">$USDT Balance</p>
            </div>

            <div className="bg-white rounded-lg shadow p-5 text-center">
              <img src={eggLogo} alt="Egg Logo" className="mx-auto w-28 h-28 object-contain" />
              <h1 className="text-3xl font-bold text-orange-600">Pets</h1>
              <div id="react-pets-card" className="mt-4"></div>
            </div>

            <div className="bg-white rounded-lg shadow p-5 text-center col-span-full lg:col-span-2">
              <h2 className="text-xl font-bold text-gray-700 mb-2">Deposit Wallet</h2>
              <p className="break-all text-gray-600 dark:text-gray-400 text-sm">
                {user.cwaddress ?? 'No wallet yet'}
              </p>
            </div>

            <div className="bg-white rounded-lg shadow p-5 text-center col-span-full">
              <h2 className="text-xl font-bold text-gray-700 mb-2">Profile</h2>
              <p className="mb-1 text-gray-600"><strong>Email:</strong> {user.email}</p>
              <a href="/profile" className="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Manage Profile
              </a>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}