import React, { useEffect, useState } from 'flowbite-react';
import { motion } from 'framer-motion';
import axios from 'axios';

export default function PetsCard() {
  const [petCount, setPetCount] = useState(0);

  useEffect(() => {
    axios.get('/api/pets/count').then(res => {
      setPetCount(res.data.count);
    }).catch(() => setPetCount(0));
  }, []);

  return (
    <motion.div initial={{ scale: 0.8 }} animate={{ scale: 1 }} transition={{ duration: 0.6 }}>
      <dt className="mb-2 text-3xl font-extrabold text-green-600">{petCount}</dt>
      <dd className="text-gray-500 dark:text-gray-400">Pets</dd>
      <button
        onClick={() => setPetCount(p => p + 1)}
        className="mt-4 bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700"
      >
        Hatch Egg 🐣
      </button>
    </motion.div>
  );
}