import { useState } from 'flowbite-react';

export default function Navbar({ user }) {
  const [open, setOpen] = useState(false);

  return (
    <nav className="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16 items-center">
          <div className="text-lg font-semibold text-gray-800 dark:text-white">
            MyPocketMonster
          </div>

          <div className="sm:hidden">
            <button onClick={() => setOpen(!open)} className="text-gray-500 dark:text-gray-300 hover:text-indigo-500">
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {open ? (
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                ) : (
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                )}
              </svg>
            </button>
          </div>

          <div className="hidden sm:flex space-x-6 items-center">
            <a href="/dashboard" className="text-gray-700 dark:text-gray-200 hover:text-indigo-600">Dashboard</a>
            <a href="/wallet" className="text-gray-700 dark:text-gray-200 hover:text-indigo-600">Wallet</a>
            <a href="/profile" className="text-gray-700 dark:text-gray-200 hover:text-indigo-600">Profile</a>
            <form method="POST" action="/logout">
              <input type="hidden" name="_token" value={window.csrf_token} />
              <button type="submit" className="text-red-600 hover:underline">Logout</button>
            </form>
          </div>
        </div>

        {open && (
          <div className="sm:hidden mt-2 space-y-2">
            <a href="/dashboard" className="block text-gray-700 dark:text-gray-300">Dashboard</a>
            <a href="/wallet" className="block text-gray-700 dark:text-gray-300">Wallet</a>
            <a href="/profile" className="block text-gray-700 dark:text-gray-300">Profile</a>
            <form method="POST" action="/logout">
              <input type="hidden" name="_token" value={window.csrf_token} />
              <button type="submit" className="block text-red-500">Logout</button>
            </form>
          </div>
        )}
      </div>
    </nav>
  );
}